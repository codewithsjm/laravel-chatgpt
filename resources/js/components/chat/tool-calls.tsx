import { getToolName, isToolUIPart } from 'ai';
import type { UIMessage } from 'ai';
import { CloudSun } from 'lucide-react';

export function ToolCalls({ message }: { message: UIMessage }) {
    const calls = message.parts.filter(isToolUIPart);

    if (!calls.length) {
        return null;
    }

    return (
        <div className="flex flex-wrap gap-2">
            {calls.map((call) => {
                const city = (call.input as { city?: string } | undefined)
                    ?.city;

                return (
                    <span
                        key={call.toolCallId}
                        className="flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs text-muted-foreground"
                    >
                        <CloudSun className="size-3.5" />
                        {getToolName(call) === 'Weather' && city
                            ? `Checked the weather in ${city}`
                            : `Used ${getToolName(call)}`}
                    </span>
                );
            })}
        </div>
    );
}
