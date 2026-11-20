import { RotateCcw, TriangleAlert } from 'lucide-react';
import { Button } from '@/components/ui/button';

export function ErrorNote({ onRetry }: { onRetry: () => void }) {
    return (
        <div className="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            <TriangleAlert className="size-4" />
            <span className="flex-1">Swali could not answer just now.</span>
            <Button size="sm" variant="outline" onClick={onRetry}>
                <RotateCcw /> Try again
            </Button>
        </div>
    );
}
