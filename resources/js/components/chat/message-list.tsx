export type ChatMessage = {
    id: string;
    role: 'user' | 'assistant';
    text: string;
};

export function MessageList({ messages }: { messages: ChatMessage[] }) {
    return (
        <div className="flex-1 overflow-y-auto">
            <div className="mx-auto flex max-w-3xl flex-col gap-6 px-4 py-8">
                {messages.map((message) =>
                    message.role === 'user' ? (
                        <div
                            key={message.id}
                            className="ml-auto max-w-[80%] rounded-3xl bg-muted px-5 py-2.5 whitespace-pre-wrap"
                        >
                            {message.text}
                        </div>
                    ) : (
                        <div key={message.id} className="leading-7">
                            {message.text}
                        </div>
                    ),
                )}
            </div>
        </div>
    );
}
