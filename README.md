# ImportDevTo Action

Import posts from a DEV.to user in Markdown format, saving `.md` files locally.

Example Workflow:

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
      - uses: erikaheidi/importDevTo@v1.1
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

Playlist tutorial completa em Português (pt-BR): https://www.youtube.com/playlist?list=PLwZiXR0tm05y3qT_yKdL-BAtn0NFykO5I
