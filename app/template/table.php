<table class="table">
  <thead>
    <tr>
      <?php foreach ( $columns as $col ): ?>
        <th><?=$col?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ( $rows as $row ): ?>
      <tr>
      <?php foreach ( $row as $cell ): ?>
        <td><?=$cell?></td>
      <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
  <?php if ( $footer && count($footer) ): ?>
  <tfoot>
    <tr>
      <?php foreach ( $footer as $foot ): ?>
        <td><?=$footer?></td>
      <?php endforeach; ?>
    </tr>
  </tfoot>
  <?php endif; ?>
</table>
