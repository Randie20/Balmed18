</main>

<footer class="site-footer">

    <div class="container footer-grid">

        <div class="footer-brand">
            <p class="eyebrow">
                Balmed 18
            </p>

            <h2>
                Made with care,
                <br>
                from Nairobi.
            </h2>

            <p>
                Small-batch body care created
                with simple ingredients and a
                whole lot of love.
            </p>
        </div>

        <div class="footer-column">
            <h3>Shop</h3>

            <a href="<?= BASE_URL ?>/pages/shop.php">
                All products
            </a>

            <a href="<?= BASE_URL ?>/pages/shop.php">
                Body butter
            </a>

            <a href="<?= BASE_URL ?>/pages/shop.php">
                Bundles
            </a>
        </div>

        <div class="footer-column">
            <h3>Contact</h3>

            <a href="mailto:hello@balmed18.com">
                hello@balmed18.com
            </a>

            <a href="tel:+254781585494">
                +254 781 585 494
            </a>

            <span>
                Nairobi, Kenya
            </span>
        </div>

    </div>

    <div class="container footer-bottom">

        <p>
            © <?= date('Y') ?> Balmed 18.
            All rights reserved.
        </p>

        <p>
            Made in Kenya.
        </p>

    </div>

</footer>

<script>
    document.addEventListener(
        'DOMContentLoaded',
        () => {
            const menuToggle =
                document.querySelector('.menu-toggle');

            const desktopNav =
                document.querySelector('.desktop-nav');

            if (!menuToggle || !desktopNav) {
                return;
            }

            menuToggle.addEventListener(
                'click',
                () => {
                    const isOpen =
                        desktopNav.classList.toggle(
                            'is-open'
                        );

                    menuToggle.setAttribute(
                        'aria-expanded',
                        String(isOpen)
                    );
                }
            );
        }
    );
</script>

</body>
</html>