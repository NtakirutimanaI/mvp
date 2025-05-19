<footer class="footer text-white text-center py-2 fixed-bottom" style="background-color: #87CEEB;">
    <div class="container">
        <a href="{{ route('mvp') }}" class="footer-link">MVP</a>
    </div>
</footer>

<style>
    .footer {
        width: 100%;
        z-index: 1030;
    }

    .footer-link {
        color: #ffffff;
        font-size: 1.2rem; /* reduced from 1.5rem */
        font-weight: bold;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-link:hover {
        color: #004085; /* darker blue on hover */
        text-decoration: underline;
    }
</style>
