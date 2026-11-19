import { ArrowUp } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';

export function Composer({
    onSend,
    busy,
}: {
    onSend: (text: string) => void;
    busy: boolean;
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
            <Button
                type="submit"
                size="icon"
                className="rounded-full"
                disabled={!text.trim() || busy}
            >
                <ArrowUp />
            </Button>
        </form>
    );
}
