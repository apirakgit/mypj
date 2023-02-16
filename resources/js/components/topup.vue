<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                      <form>
                        <div class="form-group">
                          <label for="exampleInputEmail1">ชื่อ นามสกุล</label>
                          <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" >
                        </div>
                        <div class="form-group">
                          <label for="exampleFormControlInput1">อีเมล์</label>
                          <input type="email" class="form-control" id="exampleFormControlInput1" >
                        </div>
                        <div class="form-group">
                          <label for="example-tel-input">เบอร์โทรศัพท์</label>
                          <input class="form-control" type="tel">
                        </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                      <form>
                        <section class="border p-3">

                          <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-primary active">
                              <input type="radio" name="options" id="option1" autocomplete="off" checked>100
                            </label>
                            <label class="btn btn-primary">
                              <input type="radio" name="options" id="option2" autocomplete="off"> 500
                            </label>
                            <label class="btn btn-primary">
                              <input type="radio" name="options" id="option3" autocomplete="off"> 1000
                            </label>
                            <label class="btn btn-primary">
                              <input type="radio" name="options" id="option3" autocomplete="off"> 2000
                            </label>
                          </div>

                        </section>

                        <button type="button" class="btn btn-primary">ยืนยันการทำรายการ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
