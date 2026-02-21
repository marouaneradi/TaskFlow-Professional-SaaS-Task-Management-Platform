<x-app-layout>
    <x-slot:title>Dashboard</x-slot:title>

    <div style="display:flex;align-items:center;justify-content:center;min-height:60vh;">
        <div class="empty-state">
            <div class="empty-icon">🚀</div>
            <h2 class="empty-title">Welcome to TaskFlow!</h2>
            <p class="empty-description">
                You're not part of any team yet. Create your first team to start managing tasks and collaborating with others.
            </p>
            <a href="{{ route('teams.create') }}" class="btn btn-primary btn-lg">
                + Create your first team
            </a>
        </div>
    </div>
</x-app-layout>
