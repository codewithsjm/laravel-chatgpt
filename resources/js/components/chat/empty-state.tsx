import { usePage } from '@inertiajs/react';

const suggestions = [
    'Explain Laravel queues like I am new',
    'Plan a 3-day trip to Zanzibar',
    'Write a polite late-payment reminder',
    'Fix this SQL: SELECT * FORM users',
];

export function EmptyState({ onPick }: { onPick: (text: string) => void }) {
    const { auth } = usePage().props;

    return (
        <div className="flex flex-1 flex-col items-center justify-center gap-6 px-4">
            <h1 className="text-3xl font-semibold">
                What can I help with, {auth.user.name.split(' ')[0]}?
            </h1>
            <div className="flex max-w-2xl flex-wrap justify-center gap-2">
                {suggestions.map((suggestion) => (
                    <button
                        key={suggestion}
                        type="button"
                        onClick={() => onPick(suggestion)}
                        className="rounded-full border px-4 py-2 text-sm text-muted-foreground hover:bg-muted"
                    >
                        {suggestion}
                    </button>
                ))}
            </div>
        </div>
    );
}
