export function Thinking() {
    return (
        <div className="flex gap-1 py-2" aria-label="Thinking">
            {[0, 150, 300].map((delay) => (
                <span
                    key={delay}
                    className="size-2 animate-bounce rounded-full bg-muted-foreground"
                    style={{ animationDelay: `${delay}ms` }}
                />
            ))}
        </div>
    );
}
