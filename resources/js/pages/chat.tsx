import { Head } from '@inertiajs/react';
import { index } from '@/routes/chat';

export default function Chat() {
    return (
        <>
            <Head title="Chat" />
            <div className="flex h-[calc(100svh-6rem)] flex-col">
                <p className="m-auto text-muted-foreground">Chat goes here.</p>
            </div>
        </>
    );
}

Chat.layout = {
    breadcrumbs: [{ title: 'Chat', href: index() }],
};
