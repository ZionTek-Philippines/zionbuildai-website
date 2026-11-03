{{-- "Try it!" demo popup: 96% of the viewport, opens How It Works on first open. The demo's own ✕ and "Book a Free Demo" talk back via postMessage. --}}
<div data-try-it-modal role="dialog" aria-modal="true" aria-label="ZionBuild AI live demo" class="fixed inset-0 z-[100] hidden items-center justify-center bg-on-surface/60 backdrop-blur-sm">
    <div class="h-[96dvh] w-[96vw] overflow-hidden rounded-2xl bg-surface-container-lowest shadow-2xl">
        <iframe data-src="{{ route('demo.how-it-works') }}" title="ZionBuild AI live demo" class="size-full border-0"></iframe>
    </div>
</div>
