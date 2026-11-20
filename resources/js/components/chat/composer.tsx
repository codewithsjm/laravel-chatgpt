import { ArrowUp, Sparkles, Square, Zap } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';

export function Composer({
    onSend,
    onStop,
    busy,
    smart,
    onSmartChange,
}: {
    onSend: (text: string) => void;
    onStop: () => void;
    busy: boolean;
    smart: boolean;
    onSmartChange: (smart: boolean) => void;
}) {
    const [text, setText] = useState('');

    function submit() {
        if (!text.trim() || busy) return;
        onSend(text.trim());
        setText('');
    }

    return (
        <form
            onSubmit={(event) => {
                event.preventDefault();
                submit();
            }}
            className="mx-auto mb-4 flex w-full max-w-3xl items-end gap-2 rounded-3xl border bg-background p-2 shadow-sm"
        >
            <button
                type="button"
                onClick={() => onSmartChange(!smart)}
                className="flex items-center gap-1.5 rounded-full border px-3 py-2 text-sm text-muted-foreground hover:bg-muted"
            >
                {smart ? (
                    <Sparkles className="size-4" />
                ) : (
                    <Zap className="size-4" />
                )}
                {smart ? 'Smart' : 'Fast'}
            </button>
            <textarea
                name="message"
                value={text}
                onChange={(event) => setText(event.target.value)}
                onKeyDown={(event) => {
                    // Enter sends, Shift+Enter starts a new line.
                    if (event.key === 'Enter' && !event.shiftKey) {
                        event.preventDefault();
                        submit();
                    }
                }}
                rows={1}
                placeholder="Ask anything"
                className="field-sizing-content max-h-48 flex-1 resize-none bg-transparent px-3 py-2 outline-none"
            />
            {busy ? (
                <Button
                    type="button"
                    size="icon"
                    className="rounded-full"
                    aria-label="Stop"
                    onClick={onStop}
                >
                    <Square className="fill-current" />
                </Button>
            ) : (
                <Button
                    type="submit"
                    size="icon"
                    className="rounded-full"
                    aria-label="Send"
                    disabled={!text.trim()}
                >
                    <ArrowUp />
                </Button>
            )}
        </form>
    );
}
