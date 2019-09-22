var path = window.location.pathname;
var host = window.location.hostname;
$(function(){

    $(window).hashchange(function(){
      var hash = $.param.fragment();
      if(hash == 'tambah'){
        if(path.search('Voucher')>0){
          $('.modal-title').text('Penambahan Voucher');
          $('#save-data').text('Tambahkan Voucher');
          $('#form-voucher').attr('action','tambah');
        }
        $('#modal-default').modal('show')
      }
    });
    $(window).trigger('hashchange');

    $('#modal-default').on('hide.bs.modal', function(){ //untuk mereset seluruh form crud menjadi semula
      window.history.pushState(null,null,path)
    })

    if(path.search('Fakultas/Dashboard')>0){
      hitung_mahasiswaAktif();
      transactionmonthly();
      get_graph_fakultas()
    }else if(path.search('Fakultas/Histori')>0){
      table_histori_transaksi()
    }else if(path.search('Voucher')>0){
      table_voucher()
    }

    $(document).on('click','#save-data',function(eve){
      eve.preventDefault();

      var action    = $('#form-voucher').attr('action')
      var datasend  = $('#form-voucher').serialize();

      $.ajax('http://'+host+path+'/action/'+ action,{
      dataType : 'json',
      type :'POST',
      data : datasend,
      success : function(data){
        if(data.status == 'success'){
          $('#modal-default').modal('hide')
          Swal.fire({
            type: 'success',
            title: 'Berhasil!!'
          }).then(function(){
            location.reload();
          })
        }else{
          Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: '',
            footer: '<small>ulang lagi yaaa</small>'
          })
          $('#modal-default').modal('hide')
        }
      }
    })
    })
    
});

function hitung_mahasiswaAktif(){
    $.ajax('http://'+host+path+'/action/Mahasiswa_Aktif',{
      dataType : 'json',
      type:'get',
      success : function(data){
        $('#count_mahasiswa .info-box-number').append(data.data);
      }
      
    })
}

function transactionmonthly(){
  moment.locale('id');
  var awal= moment().startOf('month').format('X');
  var akhir= moment().startOf('endOf').format('X');

  $.ajax('http://'+host+path+'/action/historyMonthly',{
    dataType : 'json',
    type:'post',
    data:{awal:awal,akhir:akhir},
    success : function(data){
      $('#count_bayar .info-box-number').append(data.data);

    }
    
  })
}

function table_voucher(){
  $('#table-voucher').DataTable({
    "ajax": "http://"+host+path+"/action/ambil",
    "columns": [
      { "data": "kode" },
      { "data": "balance",render: function(data, type, row){
        return formatRupiah(row.balance,'Rp. ')
      }},
      { "data": "isUsed",render: function(data, type, row){
        if(row.isUsed == 'f'){
          return 'Belum Digunakan'
        }else{
          return 'Sudah Digunakan'
        }
      }},
    ],
    "language": {
      emptyTable: "Belum Ada Data", // 
      loadingRecords: "Tunggu .. ", // default Loading...
      zeroRecords: "Data tidak ditemukan"
     }
  });
}

function table_histori_transaksi(){
  moment.locale('id');
  var table = $('#table-histori-transaksi').DataTable({
    "ajax": "http://"+host+path+"/action/ambil",
    "order": [[ 3, "asc" ]],
    "columns": [
      { "data": "transactionFaktur" },
      { "data": "transactionType"},
      { "data": "namaDepan ",render: function(data, type, row){
        return row.namaDepan+' '+row.namaBelakang;
      }},
      { "data": "transactionTime",render: function(data, type, row){
        return moment.unix(row.transactionTime).format('LLLL')
      }},
      { "data": "total",render: function(data, type, row){
        return formatRupiah(row.total,'Rp. ')
      }},
    ],
    "language": {
      emptyTable: "Belum Ada Data", // 
      loadingRecords: "Tunggu .. ", // default Loading...
      zeroRecords: "Data tidak ditemukan"
     }
  });

  setInterval( function () {
    table.ajax.reload(null, false);
  }, 3000 );
}

function get_graph_fakultas(){
  // $.ajax('http://'+host+path+'/action/historyMonthly',{
  //   dataType : 'json',
  //   type:'post',
  //   data:{awal:awal,akhir:akhir},
  //   success : function(data){
  //     $('#count_bayar .info-box-number').append(data.data);

  //   }
    
  // })
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
          labels: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
          datasets: [{
              label: 'Mahasiswa Aktif',
              data: [10, 20, 30, 60, 80, 120, 150],
              borderColor: 'rgba(68, 143, 218, 1)',
              backgroundColor: 'rgba(68, 143, 218, 1)',
              fill: false,
              pointRadius: 10,
						  pointHoverRadius: 15,
              borderWidth: 3
          }]
      },
      options: {
          elements: {
            line: {
              tension: 0.000001
            },
            point: {
							pointStyle: 'rectRounded'
						}
          },
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true
                  }
              }]
          }
      }
  });
}

function formatRupiah(angka, prefix){
  var number_string = angka.replace(/[^,\d]/g, '').toString(),
  split   		= number_string.split(','),
  sisa     		= split[0].length % 3,
  rupiah     		= split[0].substr(0, sisa),
  ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if(ribuan){
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }

  rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
  return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}