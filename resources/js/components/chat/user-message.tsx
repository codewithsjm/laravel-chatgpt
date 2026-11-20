import type { FileUIPart, UIMessage } from 'ai';
import { FileText } from 'lucide-react';

export function UserMessage({ message }: { message: UIMessage }) {
    const files = message.parts.filter(
        (part): part is FileUIPart => part.type === 'file',
    );
    const text = message.parts
        .map((part) => (part.type === 'text' ? part.text : ''))
        .join('');

    return (
        <div className="ml-auto flex max-w-[80%] flex-col items-end gap-2">
            {files.map((file, index) =>
                file.mediaType.startsWith('image/') ? (
                    <img
                        key={index}
                        src={file.url}
                        alt={file.filename ?? 'Image'}
                        className="max-h-60 rounded-2xl border"
                    />
                ) : (
                    <span
                        key={index}
                        className="flex items-center gap-2 rounded-xl border px-3 py-2 text-sm"
                    >
                        <FileText className="size-4" />
                        {file.filename ?? 'Document'}
                    </span>
                ),
            )}
            {text && (
                <div className="rounded-3xl bg-muted px-5 py-2.5 whitespace-pre-wrap">
                    {text}
                </div>
            )}
        </div>
    );
}
