# Swali — build your own ChatGPT in Laravel

Source code for the **Code with SJM** course
[How to Build Your Own ChatGPT in Laravel 13 (Laravel AI SDK + React, Full Course)](https://youtu.be/z8zjgZtQs_A).

Swali ("question" in Swahili) is a ChatGPT-style app: answers stream in word by word, chats are saved with AI titles,
you can stop and try again, pick Fast or Smart, set custom instructions, send images and PDFs, and the AI can call a
live weather tool.

**Stack:** Laravel 13 · [Laravel AI SDK](https://github.com/laravel/ai) · Google Gemini (free tier) · React + TypeScript
(Inertia) · Tailwind CSS 4 · shadcn/ui · Vercel AI SDK (`useChat`) · SQLite · Pest

## Follow along part by part

Every step in the video is one commit (`sw03.2 …`), and every part ends at a tag. Check out a tag to see the code
exactly as it is at the end of that part:

| Tag | Part |
|---|---|
| `sw00` | A fresh Laravel React starter kit |
| `sw01` | A fresh app and a free AI key |
| `sw02` | The chat screen |
| `sw03` | Streaming answers |
| `sw04` | Answers that look good |
| `sw05` | Saved conversations |
| `sw06` | Titles, rename, delete |
| `sw07` | Stop, try again, errors |
| `sw08` | Fast or smart, custom instructions |
| `sw09` | Images and PDFs |
| `sw10` | Give it tools |
| `sw11` | Limits and the finish |

```bash
git checkout sw05   # the code at the end of part 5
git checkout main   # back to the finished app
```

## Run it

You need PHP 8.4, Composer and Node.js. Get a free Gemini API key at https://aistudio.google.com (Get API key).

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Put your key in `.env` (never commit it):

```dotenv
GEMINI_API_KEY=your-key-here
GEMINI_MODEL=gemini-3.5-flash-lite
GEMINI_SMART_MODEL=gemini-3.5-flash
```

Then create the database and start everything:

```bash
php artisan migrate --seed
composer run dev
```

Open http://localhost:8000, register an account, and ask Swali anything.

If Gemini answers that it's busy (503) or you've used today's free requests (429), switch `GEMINI_MODEL` to another
model in `.env`: the app also fails over to a backup model on its own.

## Tests

```bash
php artisan test
```

The tests use `Assistant::fake()`, so they never call Gemini.

## License

MIT
