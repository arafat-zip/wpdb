<div class="wrap">
    <h1>Contact List</h1>
    <div class="actions" style="margin-bottom: 20px;">
        <a href="?page=db_delta&tab=add" class="button button-primary">Add New Contact</a>
        <a href="?page=db_delta&tab=edit" class="button button-secondary">Edit Contact</a>
    </div>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $table = $wpdb->prefix . 'db_delta';
            $results = $wpdb->get_results("SELECT * FROM $table");

            if (!empty($results)) {
                foreach ($results as $row) {
                    echo "<tr>";
                    echo "<td>{$row->id}</td>";
                    echo "<td>{$row->name}</td>";
                    echo "<td>{$row->phone}</td>";
                    echo "<td>{$row->email}</td>";
                    echo "<td>{$row->create_at}</td>";
                    echo "<td>{$row->update_at}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No contacts found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
