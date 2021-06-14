
<style>
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .pr-10 {
        padding-right: 10px;
    }
    .border {
        border-bottom: 1px dashed black;
    }
    table {
        width: 90% !important;
        font-size: 18px !important;
    }
    @media print
    {
        table {
            font-size: 3vw;
            font-family: arial;
        }
        @page {
            size: 48mm 3276mm;
            size: portrait;
        }
    }
</style>

<?php echo $table; ?>