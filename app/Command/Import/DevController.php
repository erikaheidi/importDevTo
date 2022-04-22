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
            throw new \Exception('You must set up your devto_username config.');
        }

        $devto_username = $this->getApp()->config->devto_username;

        $articles_response = $crawler->get($this->API_URL . '/articles?username=' . $devto_username);

        if ($articles_response['code'] !== 200) {
            throw new \Exception('Error while contacting the dev.to API.');
        }

        if (!$this->getApp()->config->data_path) {
            throw new \Exception('You must define your data_path config value.');
        }

        $data_path = $this->getApp()->config->data_path;

        if (!is_dir($data_path) && !mkdir($data_path)) {
            throw new \Exception('You must define your data_path config value.');
        }

        $articles = json_decode($articles_response['body'], true);
        foreach($articles as $article) {
            $get_article = $crawler->get($this->API_URL . '/articles/' . $article['id']);

            if ($get_article['code'] !== 200) {
                $this->getPrinter()->error('Error while contacting the dev.to API.');
                continue;
            }

            $article_content = json_decode($get_article['body'], true);
            $date = new \DateTime($article_content['published_at']);
            $filepath = $data_path . '/' . $date->format('Ymd') . '_' . $article_content['slug'] . '.md';
            $file = fopen($filepath, 'w+');
            fwrite($file, $article_content['body_markdown']);
            fclose($file);

            $this->getPrinter()->info("Saved article: " . $article_content['title'] . " to $filepath");
        }

        $this->getPrinter()->info("Finished importing.", true);
    }
}