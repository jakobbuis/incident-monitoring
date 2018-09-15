# Incident monitoring
> Dashboard for reviewing incidents on your websites

## Contents
- [Setup](#setup)
- [Dashboard](#dashboard)
- [Slack integration](#slack-integration)
- [Importing data](#importing-data)

## Setup
Setup this project as a regular Laravel-application. Setup follows the normal steps like filling in the environment file, etc. Do not forget the following:
1. Setup the [task scheduling](https://laravel.com/docs/5.7/scheduling#introduction).
1. Run the migrations.
1. Configure the Slack-webhook URL to receive notifications.

## User interface
The home route of the application renders the dashboard. There are no other views.

## Slack integration
Incident monitoring supports integration with a single Slack-channel.
[Configure a webhook](https://api.slack.com/incoming-webhooks) for your Slack-group
with a valid channel. Add this webhook to the environment file as `SLACK_WEBHOOK_URL`.

## Importing data
An Artisan commands is available to import your websites into the Incident monitoring
database. Use as follows:
```bash
php artisan websites:import data.csv
```
There are a number of optional flags to control which columns are imported, which you can review by running `php artisan websites:import -h`. Additionally, you can purge stale records (those that are not included in the file you import) by using the `--purge` option.

