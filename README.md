# Random Team

Random team is a simple project to randomize teams based on user message input in the telegram bot.

## Requirement

Because this project uses Laravel 10, requires a minimum PHP version of 8.1. For more details check out the [Laravel 10](https://laravel.com/docs/10.x/releases) documentation.

## Installation

1. Clone this project

```
git clone https://github.com/dhillen-bp/random-team-bot.git
```

2. Create Telegram Bots in [Bot Father](https://t.me/BotFather). After successfully creating a bot, you will receive a Bot Token. In my example the sensor.
   <br><img alt="Create Bot" src="https://i.imgur.com/sCLBnjf.png" width="600px"><br>

3. Run `php artisan serve`
   <br><img alt="run php artisan serve" src="https://i.imgur.com/Ize6aw8.png" width="600px"><br>

4. Run `ngrok http 8000`
   <br><img alt="run ngrok http" src="https://i.imgur.com/fWm3nZf.png" width="600px"><br>

5. Update the .env file to look like this:
   <br><img alt="update .env file" src="https://i.imgur.com/SHNen4V.png" width="600px"><br>

6. On the browser run `http://localhost:8000/api/setWebhook` to register the webhook url to the telegram bot. If true, it will display the die dump true display.

## Screenshots Demo

<img alt="update .env file" src="https://i.imgur.com/usB9n1u.png"><br>

## Learning References

-   [https://telegram-bot-sdk.com/docs](https://telegram-bot-sdk.com/docs)
-   [https://egin10.medium.com/membuat-bot-telegram-dengan-laravel-8-f9fce9b2bb56](https://egin10.medium.com/membuat-bot-telegram-dengan-laravel-8-f9fce9b2bb56)
