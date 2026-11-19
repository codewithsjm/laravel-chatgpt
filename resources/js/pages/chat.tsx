import { useChat } from '@ai-sdk/react';
import { Head } from '@inertiajs/react';
import { DefaultChatTransport } from 'ai';
import { Composer } from '@/components/chat/composer';
import { EmptyState } from '@/components/chat/empty-state';
import { MessageList } from '@/components/chat/message-list';
import { xsrfHeader } from '@/lib/csrf';
import { index, stream } from '@/routes/chat';

export default function Chat() {
    const { messages, sendMessage, status } = useChat({
        transport: new DefaultChatTransport({
            api: stream.url(),
            headers: xsrfHeader,
        }),
    });

    const send = (text: string) => sendMessage({ text });

    return (
        <>
            <Head title="Chat" />
            <div className="flex h-[calc(100svh-6rem)] flex-col">
                {messages.length ? (
                    <MessageList messages={messages} status={status} />
                ) : (
                    <EmptyState onPick={send} />
                )}
                <Composer onSend={send} />
            </div>
        </>
    );
}

Chat.layout = {
    breadcrumbs: [{ title: 'Chat', href: index() }],
};
