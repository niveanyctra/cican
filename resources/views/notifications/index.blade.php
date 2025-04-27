<h1>Notifications</h1>

<ul>
    @foreach ($notifications as $notification)
        <li>
            {{ $notification->data['message'] }}
            <a href="{{ $notification->data['link'] }}">View</a>
            <button onclick="markAsRead('{{ $notification->id }}')">Mark as Read</button>
        </li>
    @endforeach
</ul>

<script>
function markAsRead(notificationId) {
    fetch('/notifications/mark-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ id: notificationId }),
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
        location.reload(); // Refresh halaman setelah membaca notifikasi
    });
}
</script>
