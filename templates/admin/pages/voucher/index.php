
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Voucher
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="box">
    <div class="box-body">
            <table id="table-voucher" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Kode Voucher</th>
                        <th>Saldo Balance</th>
                        <th>telah digunakan</th>
                        
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Kode Voucher</th>
                        <th>Saldo Balance</th>
                        <th>telah digunakan</th>
                    </tr>
                </tfoot>
            </table>
    </div>
    </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal modal-info fade" id="modal-default">
    <div class="modal-dialog" >
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Info Modal</h4>
        </div>
        <div class="modal-body">
          <!-- select -->
          <form class="form-horizontal" action="" id="form-voucher" autocomplete="off">
          <div class="box-body">
                <div class="form-group">
                  <label for="Select" class="col-sm-2 control-label">Jumlah</label>

                  <div class="col-sm-2">
                  <select class="form-control" name="jumlah" id="jumlah">
                    <?php for ($i = 1; $i <= 100; $i++){echo "<option value='$i'>$i</option>";} ?>
                  </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="Select" class="col-sm-2 control-label">Saldo</label>
                  <div class="col-sm-5">
                  <select class="form-control" name="saldo" id="saldo">
                    <?php for ($i = 25000; $i <= 150000; $i+=25000){echo "<option value='$i'>".rupiah($i)."</option>";} ?>
                  </select>
                  </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" id="save-data">....</button>
          </form>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<!-- /.modal -->
