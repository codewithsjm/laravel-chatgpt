import type { ChatStatus, UIMessage } from 'ai';

// A message is made of parts: text, files, sources... For now, the text.
function textOf(message: UIMessage) {
    return message.parts
        .map((part) => (part.type === 'text' ? part.text : ''))
        .join('');
}

export function MessageList({
    messages,
    status,
}: {
    messages: UIMessage[];
    status: ChatStatus;
}) {
    return (
        <div className="flex-1 overflow-y-auto">
            <div className="mx-auto flex max-w-3xl flex-col gap-6 px-4 py-8">
                {messages.map((message) =>
                    message.role === 'user' ? (
                        <div
                            key={message.id}
                            className="ml-auto max-w-[80%] rounded-3xl bg-muted px-5 py-2.5 whitespace-pre-wrap"
                        >
                            {textOf(message)}
                        </div>
                    ) : (
                        <div key={message.id} className="leading-7">
                            {textOf(message)}
                        </div>
                    ),
                )}
            </div>
        </div>
    );
}
