# URL Shortener (Laravel/Vue)

Small application to shorten URLs

## Running the app

To start off, copy the `.env.example` file into a `.env` file.

Then, open your terminal in the root directory of the project and run the following commands in order.

Install composer dependencies:

```
composer install
```

Install npm dependencies and build frontend files:

```
npm install && npm run build
```

Run the application in dev mode:

```
composer run dev
```

That should start the frontend and backend and you should be able to access the app on `localhost:8000`
