import { Form } from '@inertiajs/react';
import { MoreHorizontal, Pencil, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { SidebarMenuAction } from '@/components/ui/sidebar';
import { destroy, update } from '@/routes/chat';

type Conversation = { id: string; title: string };

export function ChatActions({ conversation }: { conversation: Conversation }) {
    const [renaming, setRenaming] = useState(false);
    const [deleting, setDeleting] = useState(false);

    return (
        <>
            <DropdownMenu>
                <DropdownMenuTrigger asChild>
                    <SidebarMenuAction showOnHover aria-label="Chat actions">
                        <MoreHorizontal />
                    </SidebarMenuAction>
                </DropdownMenuTrigger>
                <DropdownMenuContent side="right" align="start">
                    <DropdownMenuItem onSelect={() => setRenaming(true)}>
                        <Pencil /> Rename
                    </DropdownMenuItem>
                    <DropdownMenuItem
                        variant="destructive"
                        onSelect={() => setDeleting(true)}
                    >
                        <Trash2 /> Delete
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>

            <Dialog open={renaming} onOpenChange={setRenaming}>
                <DialogContent>
                    <DialogTitle>Rename chat</DialogTitle>
                    <DialogDescription>
                        Give it a name you will find.
                    </DialogDescription>
                    <Form
                        {...update.form(conversation.id)}
                        onSuccess={() => setRenaming(false)}
                        className="space-y-4"
                    >
                        <Input
                            name="title"
                            defaultValue={conversation.title}
                            autoFocus
                        />
                        <DialogFooter>
                            <Button type="submit">Save</Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>

            <Dialog open={deleting} onOpenChange={setDeleting}>
                <DialogContent>
                    <DialogTitle>Delete this chat?</DialogTitle>
                    <DialogDescription>
                        "{conversation.title}" and all its messages will be
                        gone.
                    </DialogDescription>
                    <Form {...destroy.form(conversation.id)}>
                        <DialogFooter>
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() => setDeleting(false)}
                            >
                                Cancel
                            </Button>
                            <Button type="submit" variant="destructive">
                                Delete
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </>
    );
}
