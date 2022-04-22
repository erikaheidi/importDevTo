# ImportDevTo Action

This GitHub Action imports posts from a DEV.to user in Markdown format, saving `.md` files to a location of your choice. The suggested workflow will combine this action with the [create-pull-request](https://github.com/peter-evans/create-pull-request) action in order to create a new PR on your repository whenever there are new DEV articles for that user. You can also use the [update-file](https://github.com/test-room-7/action-update-file) action to commit these changes directly to the repository where the workflow is set.

## Required ENV variables

You should provide two ENV variables to the container. These are to set up the DEV username or organization to pull content from, and to set up the location where to save the .md files. To create a PR, you´ll need to save this to the same location inside $GITUB_WORKSPACE where the origin repository is checked out (you can use the `actions/checkout` action for that).

- `DEVTO_USERNAME`: user or organization to pull content from. Ex: `erikaheidi` 
- `APP_DATA_DIR`: the directory to save the .md files. Ex: `${{ github.workspace }}/devto`

## Create Pull Request - Example Workflow

This workflow will:

- run once per day at 1am UTC
- check out the repository where this workflow is defined to the location referenced via ENV as $GITHUB_WORKSPACE
- run the import dev command using the provided ENV configuration, saving the .md files to a folder called `devto` inside $GITHUB_WORKSPACE 
- create a PR if there is a diff between the repository checked out version and the content generated

```yaml
name: Import posts from DEV
on:
  schedule:
    - cron: "0 1 * * *"
  workflow_dispatch:
jobs:
  main:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: erikaheidi/importDevTo@v1.2
        name: "Import posts from DEV"
        env:
          DEVTO_USERNAME: erikaheidi
          APP_DATA_DIR: ${{ github.workspace }}/devto
      - name: Create a PR
        uses: peter-evans/create-pull-request@v3
        with:
          commit-message: Import posts from DEV
          title: "[automated] Import posts from DEV"
          token: ${{ secrets.GITHUB_TOKEN }}

```

## Video Tutorial em Português

Eu compartilhei uma série de 3 vídeos sobre como criar uma GitHub Action em PHP com o Minicli usando esse demo. A playlist tutorial completa em Português (pt-BR) está disponível aqui: https://www.youtube.com/playlist?list=PLwZiXR0tm05y3qT_yKdL-BAtn0NFykO5I
