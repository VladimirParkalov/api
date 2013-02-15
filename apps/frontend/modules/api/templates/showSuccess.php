<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $music->getId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $music->getUserId() ?></td>
    </tr>
    <tr>
      <th>Title:</th>
      <td><?php echo $music->getTitle() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $music->getDescription() ?></td>
    </tr>
    <tr>
      <th>Url:</th>
      <td><?php echo $music->getUrl() ?></td>
    </tr>
    <tr>
      <th>Image:</th>
      <td><?php echo $music->getImage() ?></td>
    </tr>
    <tr>
      <th>Is active:</th>
      <td><?php echo $music->getIsActive() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $music->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $music->getUpdatedAt() ?></td>
    </tr>
    <tr>
      <th>Slug:</th>
      <td><?php echo $music->getSlug() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('api/edit?id='.$music->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('api/index') ?>">List</a>
