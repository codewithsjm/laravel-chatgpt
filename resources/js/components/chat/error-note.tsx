import { RotateCcw, TriangleAlert } from 'lucide-react';
import { Button } from '@/components/ui/button';

// The server may explain itself in JSON ({ "message": "..." }); otherwise, a general sorry.
function reason(error?: Error) {
    try {
        return JSON.parse(error?.message ?? '').message as string;
    } catch {
        return 'Swali could not answer just now.';
    }
}

export function ErrorNote({
    error,
    onRetry,
}: {
    error?: Error;
    onRetry: () => void;
}) {
    return (
        <div className="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            <TriangleAlert className="size-4" />
            <span className="flex-1">{reason(error)}</span>
            <Button size="sm" variant="outline" onClick={onRetry}>
                <RotateCcw /> Try again
            </Button>
        </div>
    );
}
