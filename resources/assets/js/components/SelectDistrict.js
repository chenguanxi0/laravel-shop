const addressData = require('china-area-data/v3/data');
// 引入lodash工具库
import _ from 'lodash';

//注册一个select-district的组件
Vue.component('select-district',{
    //定义组件属性
    props:{
        //用来初始化省市区的名字 在编辑时会用到
        initValue:{
            type:Array, //格式为数组
            default:()=>([]), //默认值为空
        }
    },
    //定义了这个组件内的数据
    data() {
        return {
            provinces:addressData['86'],//省列表
            cities:{},//城市列表
            districts:{},//地区列表
            provinceId:'',//当前选中的省
            cityId:'',//当前选中的市
            districtId:'',//当前选中的区
        }
    },
    //定义观察器 对应属性变更时会触发对应的观察器函数
    watch:{
        // 当选择的省发生改变时触发
        provinceId(newVal){
            if (!newVal){
                this.cities = {};
                this.citiId = '';
                return;
            }
            //设置城市为当前选中省下的城市
            this.cities = addressData[newVal];
            //如果当前选中的城市不在当前省，则清空当前城市
            if (!this.cities[this.citiId]){
                this.citiId = '';
            }
        },
        // 当选择的城市发生改变时触发
        cityId(newVal){
            if (!newVal){
                this.districts = {};
                this.districtId = '';
                return;
            }
            //设置市区为当前选中城市下的市区
            this.districts = addressData[newVal];
            //如果重新选择城市后 清空当前市区
            if (!this.districts[this.districtId]){
                this.districtId = '';
            }
        },
        //当选择的区变化时
        districtId(){
            // 触发一个名为 change 的 Vue 事件，事件的值就是当前选中的省市区名称，格式为数组
            this.$emit('change', [this.provinces[this.provinceId], this.cities[this.cityId], this.districts[this.districtId]]);
        }
    },
    create(){
        this.setFromValue(this.initValue);
    },
    methods:{
        setFromValue(value){
            //过滤掉空值
            value = _.filter(value);
            // 如果数组长度为0，则将省清空（由于我们定义了观察器，会联动触发将城市和地区清空）
            if (value.length === 0){
                this.provinceId = '';
                return ;
            }
            // 从当前省列表中找到与数组第一个元素同名的项的索引
            const provinceId = _.findKey(this.provinces,o=>o === value[0]);
            //如果这个id不存在 清空省列表
            if (!provinceId) {
                this.provinceId = '';
                return ;
            }
            //如果找到了 则改变当前省 同时因为观察器 city也会跟着改变
            this.provinceId = provinceId;

            // 从当前城市列表中找到与数组第二个元素同名的项的索引
            const cityId = _.findKey(this.cities,o=>o === value[1]);
            //如果这个id不存在 清空城市列表
            if (!cityId) {
                this.cityId = '';
                return ;
            }
            //如果找到了 则改变当前城市 同时因为观察器 市也会跟着改变
            this.cityId = cityId;

            // 从当前市列表中找到与数组第三个元素同名的项的索引
            const districtId = _.findKey(this.districts,o=>o === value[1]);
            //如果这个id不存在 清空市列表
            if (!districtId) {
                this.districtId = '';
                return ;
            }
            //如果找到了 则改变当前市
            this.districtId = districtId;
        }
    }
});