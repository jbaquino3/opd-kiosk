<template>
  <div>
      <div class="content-header">
          <div class="container-fluid">
              <h3>OPD Follow-up Patients ({{ this.dateNow | dateOnly }})</h3>
          </div>
          <!-- .content-header -->
          <section class="content">
              <div class="container-fluid">
                  <div class="table-responsive">
                      <div>
                        <small class="text-muted">Below are the list of patients scheduled for a follow-up checkup today.</small>
                        <div class="input-group float-right mb-1 col-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search text-info"></i></span>
                            </div>
                            <input id="input_search" class="form-control form-control-sm" placeholder="Search">   
                        </div>
                      </div>
                      <table class="table table-sm table-bordered table-hover">
                          <thead>
                              <th scope="col" width="20%">Date/Time of Previous Checkup</th>
                              <th scope="col" width="15%">Hospital Number</th>
                              <th scope="col" width="">Type</th>
                              <th scope="col">Patient Name</th>
                              <th scope="col">Department</th>
                              <th scope="col">Date encoded</th>
                              <th scope="col">Scheduled By</th>
                              <th scope="col">Follow-up</th>
                          </thead>
                          <tbody>
                              <!-- <tr v-for="pat in followup" :key="pat.hpercode" @click="generate_queue(pat.hpercode, pat.tscode)"> -->
                              <tr v-for="pat in followup" :key="pat.tStamp" @click="generate_queue(pat.hpercode, pat.tscode)">
                                  <td>{{ pat.tStamp | standard_datetime }}</td>
                                  <td>{{ pat.hpercode }}</td>
                                  <td>
                                      <span class="font-weight-bold text-success" v-if="pat.teleconsultation==0"><i class="fa fa-user-friends"></i></span>
                                      <span class="font-weight-bold text-primary" v-else-if="pat.teleconsultation==1"><i class="fa fa-mobile-alt fa-lg"></i></span>
                                      <span class="font-weight-bold" v-else></span>
                                  </td>
                                  <td>{{ pat.fullname }}</td>
                                  <td>{{ pat.tsdesc }}</td>
                                  <td>{{ pat.dateEncoded | standard_datetime }}</td>
                                  <td>{{ pat.scheduledby}}</td>
                                  <td>
                                      <span v-if="pat.isReg"> <button class="btn btn-success readonly"></button> </span>
                                      <span v-else></span>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
          </section>
      </div>

      <!-- queue modal -->
        <div class="modal fade" id="mod_ffqueue" role="dialog" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <!-- print this -->
                        <div id="print_section" class="container text-center border center mx-auto">
                            <h1 class="display-1 m-0"><b style="font-size: 1.4em !important;">{{ this.queue }}</b></h1>
                            <h4 class="department">
                                <b style="font-size: 1.4em;">{{ this.department }}</b>
                            </h4>
                            <small class="form-text text-muted" style="font-size: 1.2em;">{{ this.date_now | standard_datetime }}</small>
                            <small class="form-text text-muted" style="font-size: 1.2em;">{{ this.fullname }} {{ this.direction }}</small>                        
                        </div>
                        <!-- print this -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click="cancel_queue()" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
                        <button type="button" class="btn btn-primary" @click="print_queue()"><i class="fa fa-print"></i>&nbsp; Print</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- .queue modal -->

        <!-- modal loading -->
        <div class="modal" id="mod_loading" tabindex="-1" role="dialog" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h3>Generating Queue...</h3>
                        <h1><i class="fa fa-spinner fa-spin"></i></h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal loading -->
  </div>
</template>

<script>
import moment from 'moment'

export default {
    data() {
        return {
            followup: [],

            pat_credentials: new Form({
                hpercode: '',
                priority: 0,
                tscode: '',
                opd_type: 'FFUP'
            }),

            enccode: '',
            queue: '',
            department: '',
            date_now: '',
            fullname: '',
            direction: '',
            dateNow: '',

            opd_print: new Form({
                enccode: '',
                pat_type: 0,
                phic: 0,
                ref_hospital: ''
            })
        }
    },

    methods: {
        getFollowupPatients() {
            axios.get('api/getFollowupPatients').then(
                ({data}) => {
                    this.followup = data;
                }
            )
        },

        generate_queue(hpercode, tscode) {
            axios.get('api/checkEncounter?hpercode=' + hpercode).then(
                ({data}) => {
                    if ( data == 0) {
                        // no encounter today
                        Swal.fire({
                            title: 'Generate Queue?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes!'
                            }).then((result) => {
                            if (result.value) {
                                $('#mod_loading').modal('show');
                                this.pat_credentials.hpercode = hpercode;
                                this.pat_credentials.tscode = tscode;

                                this.pat_credentials.post("api/generate_queue").then(
                                    ({data}) => {
                                        this.opd_print.enccode = data[0]['enccode'];
                                        this.queue = data[0]['filling'];
                                        this.fullname = data[0]['fullname'],
                                        this.date_now = data[0]['datenow'];
                                        this.direction = data[0]['directions'];
                                        this.department = data[0]['department'];
                                        $('#mod_loading').modal('hide');
                                        $('#mod_ffqueue').modal('show');
                                    }
                                )
                            }
                        })
                    } else {
                        // patient already has encounter
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'It seems like the patient already has an encounter today!'
                        })
                    }
                }
            )     
        },

        cancel_queue() {
            axios.get("api/cancel_queue?enccode=" + this.opd_print.enccode).then(
                ({data}) => {
                    // simple cancellation of the registered queue
                    console.log('deleted queue');
                }
            );
        },

        print_queue() {
            /**
             * We first have to check if the patient type is senior or not
             * We can do so by using an if else condition that queries 
             * if patient type is 1 from the global variable then patient is senior
             * If not, then we have to get the variable on this component specified by the user.
             * 
             * Other things we have to consider is the hospital referral or the PHIC insurance
             * but it does not really affect initial queuing of the patient so it's fine.
             */
            
            this.opd_print.post("api/print_queue").then(
                ({data}) => {
                    // print queue here (javascript)
                    try {
                        this.$htmlToPaper('print_section');
                    } catch(ex) {

                    }
                    $('#mod_ffqueue').modal('hide');
                    this.$router.push('/follow_up');
                }
            );
        },

        getDate() {
   
            var currentDate = new Date();
            console.log(currentDate);
  
            var currentDateWithFormat = new Date().toJSON().slice(0,10).replace(/-/g,'/');
            console.log(currentDateWithFormat);

            this.dateNow = currentDateWithFormat;
        }
    },

    created() {
        this.getFollowupPatients();
        this.getDate();
        
        $(function(){
            $("#input_search").trigger('focus');
            let filterSearch = $("#input_search");
            filterSearch.on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    }
}
</script>

<style>

</style>