
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <a class="footer-brand" href="<?= url('index.php') ?>">Balmed 18</a>
            <p>Bottled beauty, made with care in Nairobi.</p>
            <p class="footer-note">Small batch. Thoughtfully made.</p>
        </div>

        <div>
            <h3>Shop</h3>
            <a href="<?= url('products.php') ?>">All products</a>
            <a href="<?= url('cart.php') ?>">Cart</a>
            <a href="<?= url('checkout.php') ?>">Checkout</a>
        </div>

        <div>
            <h3>Contact</h3>
            <a href="tel:+254700000000">+254 700 000 000</a>
            <a href="mailto:hello@balmed18.co.ke">hello@balmed18.co.ke</a>
            <span>Nairobi, Kenya</span>
        </div>
    </div>

    <div class="container footer-bottom">
        <span>&copy; <?= date('Y') ?> Balmed 18. All rights reserved.</span>
        <a href="<?= url('admin/login.php') ?>">Admin</a>
    </div>
</footer>

<script src="<?= url('assets/js/main.js') ?>"></script>
</body>
</html>
