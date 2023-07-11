<div class="container">
    <div class="row">
        <div class="col-sm-12 pt-5">
            <h1>Peregrine Referral Program</h1>
            <p class="lead">
                For every customer you refer that places an order you will earn $100 (Visa Gift Card), or $120 in credit on your PMI account with us. Your info has been auto-filled to save you the hassle of entering it. After you submit the referral we'll take it from there.
            </p>
        </div>
    </div>
    <?
    $chk = mysqli_query($link, 'SELECT * FROM referral_users WHERE id=\''.make_safe($_SESSION['rid']).'\' LIMIT 1');
    ?>
    <form id="refer" action="<?=root('do/new/'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" value="<?=mysqli_result($chk,0,'id'); ?>" id="rid" name="rid"/>
        <div class="row">
            <div class="col-sm-6 pt-5">
                <h2>Your Info</h2>
                <div class="form-group">
                    <label for="cname" class="control-label">
                        <strong>Name:</strong>
                    </label>
                    <input type="text" value="<?=mysqli_result($chk,0,'name'); ?>" name="cname" id="cname" autocomplete="off" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="caddress" class="control-label">
                        <strong>Full Address:</strong>
                    </label>
                    <input type="text" value="<?=mysqli_result($chk,0,'address'); ?>" name="caddress" id="caddress" autocomplete="off" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="cemail" class="control-label">
                        <strong>Email:</strong>
                    </label>
                    <input type="text" value="<?=mysqli_result($chk,0,'email'); ?>" name="cemail" id="cemail" autocomplete="off" class="form-control"/>
                </div>
                <h2>Customer Info</h2>
                <div class="form-group">
                    <label for="pname" class="control-label">
                        <strong>Name:</strong>
                    </label>
                    <input type="text" value="<?=$_SESSION['pname']; ?>" name="pname" id="pname" autocomplete="off" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="paddress" class="control-label">
                        <strong>Full Address:</strong>
                    </label>
                    <input type="text" value="<?=$_SESSION['paddress']; ?>" name="paddress" id="paddress" autocomplete="off" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="puspa" class="control-label">
                        <strong>USPA License Number:</strong>
                    </label>
                    <input type="text" value="<?=$_SESSION['puspa']; ?>" name="puspa" id="puspa" autocomplete="off" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="pemail" class="control-label">
                        <strong>Email:</strong>
                    </label>
                    <input type="email" value="<?=$_SESSION['pemail']; ?>" name="pemail" id="pemail" autocomplete="off" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="pphone" class="control-label">
                        <strong>Phone:</strong>
                    </label>
                    <input type="number" value="<?=$_SESSION['pphone']; ?>" name="pphone" id="pphone" autocomplete="off" class="form-control"/>
                </div><br />
                <p>Preferred method of contact</p>
                <div class="form-group form-check">
                    <label for="pcphone" class="form-check-label">
                      <input class="form-check-input" type="radio" name="pcontact" id="pcphone" value="Phone" <? if ($_SESSION['pcontact']=='Phone') echo 'checked="checked"'; ?>/> Phone
                    </label>

                </div>
                <div class="form-group form-check">
                    <label for="pcemail" class="form-check-label">
                      <input class="form-check-input" type="radio" name="pcontact" id="pcemail" value="Email" <? if ($_SESSION['pcontact']=='Email') echo 'checked="checked"'; ?>/> Email
                    </label>

                </div>
                <div class="form-group form-check">
                    <label for="pcvc" class="form-check-label">
                      <input class="form-check-input" type="radio" name="pcontact" id="pcvc" value="Video Conference" <? if ($_SESSION['pcontact']=='Video Conference') echo 'checked="checked"'; ?>/> Video Conference
                    </label>
                </div>
                <div class="form-group form-check">
                    <label for="pcfb" class="form-check-label">
                      <input class="form-check-input" type="radio" name="pcontact" id="pcfb" value="Facebook" <? if ($_SESSION['pcontact']=='Facebook') echo 'checked="checked"'; ?>/> Facebook
                    </label>
                </div>
                <br/>
                <p>Preferred day and time of contact:</p>
                <?
                $selDT = 'now';
                $ddt   = date('m-d-Y H:i');
                if (isset($_SESSION['ppdate']) && !empty($_SESSION['ppdate'])) {
                    $selDT = $_SESSION['ppdate'];
                    $ddt   = $_SESSION['ppdate'];
                }
                ?>
                <div id="picker"></div>
                <input type="hidden" id="ppdate" name="ppdate" value="<?=$ddt; ?>"/>
            </div>
            <div class="col-sm-6 pt-5">
                <h2>Customer Photo</h2>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-default btn-file">
                                <strong>Browse…</strong> <input type="file" id="imgInp" name="ppicture">
                            </span>
                        </span>
                        <input type="text" class="form-control" readonly>
                    </div>
                </div>
                <hr />
                <img id="img-upload" src="" class="img-fluid"/>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-right">
                <hr/>
                <button type="submit" class="btn btn-primary" name="submit">Create Referral</button>
                <button type="button" class="btn btn-primary" onclick="location='<?=root(); ?>';">Cancel</button>
            </div>
        </div>
    </form>
</div>
<script>
    $( document ).ready( function () {
        $( document ).on( 'change', '.btn-file :file', function () {
            var input = $( this ),
                label = input.val().replace( /\\/g, '/' ).replace( /.*\//, '' );
            input.trigger( 'fileselect', [ label ] );
        } );

        $('#picker').dateTimePicker({
            selectData: "<?=$selDT; ?>",
            dateFormat: "MM-DD-YYYY HH:mm",
            positionShift: { top: 0, left: 0},
            title: "Select Date and Time",
            buttonTitle: "Select"
        });

        $( '.btn-file :file' ).on( 'fileselect', function ( event, label ) {

            var input = $( this ).parents( '.input-group' ).find( ':text' ),
                log = label;

            if ( input.length ) {
                input.val( log );
            } else {
                if ( log ) alert( log );
            }

        } );

        function readURL( input ) {
            if ( input.files && input.files[ 0 ] ) {
                var reader = new FileReader();

                reader.onload = function ( e ) {
                    $( '#img-upload' ).attr( 'src', e.target.result );
                }

                reader.readAsDataURL( input.files[ 0 ] );
            }
        }

        $( '#imgInp' ).change( function () {
            readURL( this );
        } );
    } );
</script>
<?
$vars = array( 'pname', 'paddress', 'puspa', 'pemail', 'pphone', 'pcontact', 'ppdate' );
unset_sess( $vars );
?>