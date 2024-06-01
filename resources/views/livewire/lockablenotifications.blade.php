@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        window.Echo.private('App.Models.User.{{ Auth::id() }}')
            .listen('.ModelUnlockRequested', (e) => {
                console.log("ModelUnlockRequested");
            })
            .listen('.ModelWasUnlocked', (e) => {
                console.log("ModelUnlocked");
            });
    });
</script>
@endpush
