<template>
    <div class="img-upload" :style="{ 'height': height + 'px', 'width': width + 'px' }">
        <template v-if="cropImg">
            <div class="img-upload_wrapper">
                <img class="img-fluid" :src="cropImg" />
                <div class="img-upload-actions">
                    <el-button type="danger" @click="removeImage" class="text-white" size="large">
                        <!-- <Icon icon="clarity:trash-solid" :inline="true" /> -->
                        <i class="fa fa-trash"></i>
                    </el-button
                    >
                </div>
            </div>
        </template>
        <div v-else class="img-upload_wrapper">
            <div class="img-upload_box">
                <input type="file" name="image" accept="image/*" @change="setImage">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" font-size="1.5rem" class="iconify iconify--mdi" width="1em" height="1em"
                    viewBox="0 0 24 24">
                    <path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"></path>
                </svg>
            </div>
        </div>
        <el-dialog v-model="modalOpen" title="Potong Gambar" width="25%" center>
            <vue-cropper ref="cropper" :aspect-ratio="ratio" :src="imgSrc" :cropBoxResizable="true" />
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="modalOpen = false">Batal</el-button>
                    <el-button type="primary" @click="submitImage">
                        Simpan
                    </el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>
<script>

import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";
export default {
    name: 'UploadImage',
    components: {
        VueCropper,
    },
    props : {
        ratio : {
            type : Number,
            default : 1/1
        },
        height : {
            type : Number,
            default : 150
        },
        width : {
            type : Number,
            default : 150
        },
        imageWidth : {
            type : Number,
            default : 600,
        },
        imageHeight : {
            type : Number,
            default : 600,
        },
        modelValue : {
            type : [ String, File ]
        }
    },
    data() {
        return {
            imgSrc: null,
            cropImg: this.image,
            filename: "",
            mimeType: "",
            modalOpen: false,
            heightWrap : null,
        };
    },
    watch: {
        image(value) {
            this.cropImg = value;
            if(value){
                this.heightWrap = '100%';
            }else{
                this.heightWrap = this.height+'px';
            }
        },
        cropImg(value){
            if(value){
                this.heightWrap = '100%';
            }else{
                this.heightWrap = this.height+'px';
            }
        },
        // modelValue(value){
        //     // if(typeof v === 'string' || v instanceof String){
        //         this.cropImg = value;
        //     // }
        // }
    },
    created(){
        if(typeof this.modelValue === 'string' || this.modelValue instanceof String){
            this.cropImg = this.modelValue;
        }
    },
    methods: {
        getHeightWrap(){
            if(this.cropImg){
                this.heightWrap = '100%';
            }else{
                this.heightWrap = this.height+'px';
            }
        },
        setImage(e) {
            const file = e.target.files[0];

            if (!file.type.includes('image/')) {
                alert('Please select an image file');
                return;
            }
            if (typeof FileReader === 'function') {
                const reader = new FileReader();

                reader.onload = (event) => {
                    this.imgSrc = event.target.result;
                    this.$refs.cropper.replace(event.target.result);
                };

                reader.readAsDataURL(file);
            } else {
                alert('Sorry, FileReader API not supported');
            }
            
            this.filename = file.name
            this.mimeType = file.type
            this.modalOpen = true;
            this.getHeightWrap();
        },
        async cropImage() {
            this.cropImg = this.$refs.cropper.getCroppedCanvas({
                minWidth: this.imageWidth,
                minHeight: this.imageHeight,
                fillColor: '#fff',
                imageSmoothingEnabled: false,
                imageSmoothingQuality: 'high',
            }).toDataURL();
            this.getHeightWrap();
        },
        async dataURLToFile(imageString, filename, mimeType) {
            const res = await fetch(imageString);
            const blob = await res.blob();
            return new File([blob], filename, {
                type: mimeType
            });
        },
        async submitImage() {
            await this.cropImage();
            const imageFileResponse = await this.dataURLToFile(this.cropImg, this.filename, this.mimeType);

            this.$emit('update:modelValue', imageFileResponse);
            this.modalOpen = false;
        },
        removeImage(){
            this.getHeightWrap();
            this.cropImg = null;
            this.$emit('update:modelValue', this.cropImg);
        },
        close(){
            this.cropImg = null;
            this.modalOpen = false;
            this.imimgSrc = null;
            this.$emit('close');
        }
    },
}
</script>