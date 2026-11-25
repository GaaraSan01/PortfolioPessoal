<?php

?>
<?php if (empty($hide_layout)): ?>
        </div> <!-- fechamento admin-content -->
    </main> <!-- fechamento admin-main -->
<?php endif; ?>

<?php if (!empty($extra_js)): ?>
    <?= $extra_js ?>
<?php endif; ?>
<script src="<?= BASE_URL ?>/admin/assets/js/admin.js"></script>
</body>
</html>
