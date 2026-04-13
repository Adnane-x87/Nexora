<footer id="about">
    <div class="footer-top">
        <div class="footer-brand">
            <a href="{{ url('/') }}" class="nav-logo" style="font-size:26px;margin-bottom:16px;display:block;">NEX<span>ORA</span></a>
            <p class="footer-desc">Next-generation electronics for people who demand more. Curated selection, unbeatable prices, lightning-fast delivery.</p>
            <div class="social-links">
                <a href="#" class="social-btn" aria-label="X">
                    <i class="bi bi-twitter-x" style="font-size:16px;line-height:1;"></i>
                </a>
                <a href="#" class="social-btn" aria-label="Instagram">
                    <i class="bi bi-instagram" style="font-size:16px;line-height:1;"></i>
                </a>
                <a href="#" class="social-btn" aria-label="Facebook">
                    <i class="bi bi-facebook" style="font-size:16px;line-height:1;"></i>
                </a>
                <a href="#" class="social-btn" aria-label="YouTube">
                    <i class="bi bi-youtube" style="font-size:16px;line-height:1;"></i>
                </a>
            </div>
        </div>
        <div>
            <div class="footer-col-title">Shop</div>
            <div class="footer-links">
                <a href="{{ url('/shop?category=Laptops') }}">Laptops</a>
                <a href="{{ url('/shop?category=Gaming') }}">Gaming</a>
                <a href="{{ url('/shop?category=Audio') }}">Audio</a>
                <a href="{{ url('/shop?category=Monitors') }}">Monitors</a>
                <a href="{{ url('/shop?category=Accessories') }}">Accessories</a>
            </div>
        </div>
        <div>
            <div class="footer-col-title">Company</div>
            <div class="footer-links">
                <a href="#">About Us</a>
                <a href="#">Careers</a>
                <a href="#">Press</a>
                <a href="#">Blog</a>
            </div>
        </div>
        <div>
            <div class="footer-col-title">Help</div>
            <div class="footer-links">
                <a href="#">FAQ</a>
                <a href="#">Shipping Info</a>
                <a href="#">Returns</a>
                <a href="#">Track Order</a>
                <a href="#">Contact</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="footer-copy">© {{ date('Y') }} NEXORA. All rights reserved.</div>
        <div class="footer-legal">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </div>
</footer>
