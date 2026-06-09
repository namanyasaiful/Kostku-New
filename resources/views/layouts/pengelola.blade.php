<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') | Kostku</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<script>
const VAPID_PUBLIC_KEY = "{{ config('webpush.vapid.public_key') }}";

async function subscribePush() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

    const reg = await navigator.serviceWorker.register('/sw.js');
    const permission = await Notification.requestPermission();
    if (permission !== 'granted') return;

    const existing = await reg.pushManager.getSubscription();
    if (existing) return sendSubscriptionToServer(existing);

    const sub = await reg.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY)
    });

    sendSubscriptionToServer(sub);
}

function sendSubscriptionToServer(sub) {
    const json = sub.toJSON();
    fetch('{{ route('push.subscribe') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            endpoint: sub.endpoint,
            public_key: json.keys?.p256dh,
            auth_token: json.keys?.auth,
            content_encoding: 'aesgcm'
        })
    });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = atob(base64);
    return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
}

subscribePush();
</script>

<body>

    <x-sidebar.pengelola />


    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</body>

</html>