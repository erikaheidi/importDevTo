<?php


namespace App\Command\Import;

use Minicli\Curly\Client;
use Minicli\Command\CommandController;

class DevController extends CommandController
{
    public string $API_URL = 'https://dev.to/api';

    public function handle(): void
    {
        $this->getPrinter()->display('Fetching posts from DEV...');
        $crawler = new Client();

        if (!$this->getApp()->config->devto_username) {
            $this->getPrinter()->error('You must set up your devto_username config');
            return;
        }

        $devto_username = $this->getApp()->config->devto_username;

        $articles_response = $crawler->get($this->API_URL . '/articles?username=' . $devto_username);

        if ($articles_response['code'] !== 200) {
            $this->getPrinter()->error('Error while contacting the dev.to API.');
            return;
        }

        if (!$this->getApp()->config->data_path) {
            $this->getPrinter()->error('You must define your data_path config value.');
            return;
        }

        $data_path = $this->getApp()->config->data_path;

        $articles = json_decode($articles_response['body'], true);
        foreach($articles as $article) {
            $get_article = $crawler->get($this->API_URL . '/articles/' . $article['id']);

            if ($get_article['code'] !== 200) {
                $this->getPrinter()->error('Error while contacting the dev.to API.');
                continue;
            }

            $article_content = json_decode($get_article['body'], true);
            $filepath = $data_path . '/' . $article_content['slug'] . '.md';
            $file = fopen($filepath, 'w+');
            fwrite($file, $article_content['body_markdown']);
            fclose($file);

            $this->getPrinter()->info("Saved article: " . $article_content['title'] . " to $filepath");
        }

        $this->getPrinter()->info("Finished importing.", true);
    }
}