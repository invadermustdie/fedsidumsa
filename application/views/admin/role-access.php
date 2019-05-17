<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <div class="row">
        <div class="col-lg-6">
            <!-- Si se adiciona de forma correcta -->
            <?= $this->session->flashdata('message'); ?>

            <h5>Role : <?= $role['role']; ?></h5>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Acceso</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                <?php foreach ($menu as $m): ?>
                    <tr>
                        <th scope="row"><?php echo $count;
                            $count++; ?></th>
                        <td><?= $m['menu']; ?></td>
                        <td>
                            <div class="form_check">
                                <input type="checkbox"
                                       class="form-check-input" <?= check_access($role['id_rol'], $m['id']); ?>
                                       data-role = "<?= $role['id_rol']; ?>"
                                       data-menu = "<?= $m['id']; ?>">
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
