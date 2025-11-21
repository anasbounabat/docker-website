<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Laravel Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="font-family: Arial, sans-serif; background:#050505; color:#fff; margin:0;">
    <div style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:40px 20px;">
        <div style="width:100%; max-width:520px; border-radius:28px; background:#111; padding:32px; box-shadow:0 20px 50px rgba(0,0,0,0.7);">
            <h1 style="margin-top:0; text-align:center; font-size:2.5rem;">Connexion</h1>
            <p style="text-align:center; color:#bbb;">Connecte-toi pour retrouver tes conversations.</p>
            <form method="POST" action="{{ route('login') }}" style="margin-top:32px;">
                @csrf
                <div style="display:flex; flex-direction:column; gap:18px;">
                    <label style="display:flex; flex-direction:column; gap:6px;">
                        <span style="font-size:0.85rem; color:#ccc;">Adresse email</span>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            style="padding:12px 14px; border-radius:10px; border:2px solid #0f8; background:#fff; color:#000;">
                    </label>
                    @error('email')
                        <span style="color:#f66; font-size:0.8rem;">{{ $message }}</span>
                    @enderror
                    <label style="display:flex; flex-direction:column; gap:6px;">
                        <span style="font-size:0.85rem; color:#ccc;">Mot de passe</span>
                        <input id="password" name="password" type="password" required
                            style="padding:12px 14px; border-radius:10px; border:2px solid #0f8; background:#fff; color:#000;">
                    </label>
                    @error('password')
                        <span style="color:#f66; font-size:0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div style="margin:24px 0; display:flex; align-items:center; justify-content:space-between; font-size:0.85rem;">
                    <label style="display:flex; align-items:center; gap:8px; color:#ccc;">
                        <input id="remember" name="remember" type="checkbox" style="width:16px; height:16px;">
                        Se souvenir
                    </label>
                    <a href="{{ route('register') }}" style="color:#0f8;">Créer un compte</a>
                </div>
                <button type="submit" style="width:100%; padding:14px; border:none; border-radius:12px; background:linear-gradient(90deg,#0f8,#0c6); font-weight:600; text-transform:uppercase; color:#fff;">Se connecter</button>
            </form>
            <p style="margin-top:14px; font-size:0.8rem; text-align:center; color:#999;">Pas encore inscrit ? <a href="{{ route('register') }}" style="color:#0f8;">Inscris-toi</a></p>
        </div>
    </div>
</body>
</html>

