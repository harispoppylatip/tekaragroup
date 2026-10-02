@props(['member'])

<article class="group relative flex flex-col rounded-2xl border border-line bg-white p-5 transition duration-300 hover:-translate-y-1 hover:border-brand/40 hover:shadow-xl hover:shadow-brand/5">
    <x-member-avatar :member="$member" class="aspect-square w-full rounded-xl text-5xl" />

    <h3 class="mt-5 font-display text-lg font-semibold text-ink">
        <a href="{{ route('members.show', $member) }}" class="after:absolute after:inset-0">{{ $member->name }}</a>
    </h3>
    <p class="mt-1 text-sm font-medium text-brand">{{ $member->role }}</p>
    <p class="mt-3 text-sm leading-relaxed text-ink-soft">{{ $member->headline }}</p>

    <ul class="mt-4 mb-5 flex flex-wrap gap-1.5" aria-label="Keahlian utama">
        @foreach ($member->topSkills(3) as $skill)
            <li class="rounded-md bg-canvas px-2 py-1 text-xs text-ink-soft">{{ $skill }}</li>
        @endforeach
    </ul>

    <span class="mt-auto flex items-center justify-between border-t border-line pt-4 text-sm font-semibold text-ink transition group-hover:text-brand">
        Lihat CV
        <x-icon name="arrow-right" class="size-4 transition group-hover:translate-x-1" />
    </span>
</article>
