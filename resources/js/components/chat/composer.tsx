import {
    ArrowUp,
    FileText,
    Paperclip,
    Sparkles,
    Square,
    X,
    Zap,
} from 'lucide-react';
import { useRef, useState } from 'react';
import { Button } from '@/components/ui/button';

export function Composer({
    onSend,
    onStop,
    busy,
    smart,
    onSmartChange,
}: {
    onSend: (text: string, files?: FileList) => void;
    onStop: () => void;
    busy: boolean;
    smart: boolean;
    onSmartChange: (smart: boolean) => void;
}) {
    const [text, setText] = useState('');
    const [files, setFiles] = useState<FileList | null>(null);
    const picker = useRef<HTMLInputElement>(null);

    function submit() {
        if ((!text.trim() && !files) || busy) return;
        onSend(text.trim(), files ?? undefined);
        setText('');
        setFiles(null);
        if (picker.current) picker.current.value = '';
    }

    return (
        <form
            onSubmit={(event) => {
                event.preventDefault();
                submit();
            }}
            className="mx-auto mb-4 w-full max-w-3xl rounded-3xl border bg-background p-2 shadow-sm"
        >
            {files && (
                <div className="flex flex-wrap items-center gap-2 p-2">
                    {Array.from(files).map((file) => (
                        <span
                            key={file.name}
                            className="flex items-center gap-1.5 rounded-lg border px-2 py-1 text-sm"
                        >
                            <FileText className="size-4" /> {file.name}
                        </span>
                    ))}
                    <button
                        type="button"
                        aria-label="Remove files"
                        onClick={() => setFiles(null)}
                    >
                        <X className="size-4" />
                    </button>
                </div>
            )}
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
                className="field-sizing-content max-h-48 w-full resize-none bg-transparent px-3 py-2 outline-none"
            />
            <div className="flex items-center gap-2">
                <input
                    ref={picker}
                    type="file"
                    name="files"
                    multiple
                    accept="image/*,application/pdf"
                    className="hidden"
                    onChange={(event) =>
                        setFiles(
                            event.target.files?.length
                                ? event.target.files
                                : null,
                        )
                    }
                />
                <Button
                    type="button"
                    size="icon"
                    variant="ghost"
                    className="rounded-full"
                    aria-label="Attach files"
                    onClick={() => picker.current?.click()}
                >
                    <Paperclip />
                </Button>
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
                {busy ? (
                    <Button
                        type="button"
                        size="icon"
                        className="ml-auto rounded-full"
                        aria-label="Stop"
                        onClick={onStop}
                    >
                        <Square className="fill-current" />
                    </Button>
                ) : (
                    <Button
                        type="submit"
                        size="icon"
                        className="ml-auto rounded-full"
                        aria-label="Send"
                        disabled={!text.trim() && !files}
                    >
                        <ArrowUp />
                    </Button>
                )}
            </div>
        </form>
    );
}
