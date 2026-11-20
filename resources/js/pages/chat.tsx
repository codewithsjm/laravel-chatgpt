import { useChat } from '@ai-sdk/react';
import { Head, router } from '@inertiajs/react';
import { DefaultChatTransport } from 'ai';
import type { UIMessage } from 'ai';
import { Composer } from '@/components/chat/composer';
import { EmptyState } from '@/components/chat/empty-state';
import { MessageList } from '@/components/chat/message-list';
import { xsrfHeader } from '@/lib/csrf';
import { index, show, stream } from '@/routes/chat';

type Props = {
    id: string;
    messages: UIMessage[];
};

export default function ChatPage(props: Props) {
    // Another conversation? Start a fresh chat on screen.
    return <Chat key={props.id} {...props} />;
}

function Chat({ id, messages: saved }: Props) {
    const { messages, sendMessage, regenerate, status, stop } = useChat({
        id,
        messages: saved,
        transport: new DefaultChatTransport({
            api: stream.url(),
            headers: xsrfHeader,
        }),
        onFinish: () => router.reload({ only: ['conversations'] }),
    });

    function send(text: string) {
        void sendMessage({ text });

        // The first message turns /chat into /chat/{id}, keeping the chat on screen.
        if (!messages.length) {
            router.replace({ url: show.url(id), preserveState: true });
        }
    }

    return (
        <>
            <Head title="Chat" />
            <div className="flex h-[calc(100svh-6rem)] flex-col">
                {messages.length ? (
                    <MessageList
                        messages={messages}
                        status={status}
                        onRegenerate={() => void regenerate()}
                    />
                ) : (
                    <EmptyState onPick={send} />
                )}
                <Composer
                    onSend={send}
                    onStop={stop}
                    busy={status === 'submitted' || status === 'streaming'}
                />
            </div>
        </>
    );
}

ChatPage.layout = {
    breadcrumbs: [{ title: 'Chat', href: index() }],
};
