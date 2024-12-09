<h2>User List</h2>

<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($authors as $author): ?>
		<tr>
			<td><?= $author->name ?></td>
			<td><?= $author->email ?></td>
			<td>
				<a href="/author/permissions/<?= $author->id ?>">Edit Permissions</a>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>