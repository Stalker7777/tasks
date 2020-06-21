<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$url_get_tasks_data = Url::to(['site/get-tasks-data']);
$url_set_tasks_data_edit = Url::to(['site/set-tasks-data-edit']);
$url_get_tasks_data_edit = Url::to(['site/get-tasks-data-edit']);
$url_get_staff_data = Url::to(['site/get-staff-data']);
$url_set_staff_data_edit = Url::to(['site/set-staff-data-edit']);
$url_get_staff_data_edit = Url::to(['site/get-staff-data-edit']);

$this->title = 'Управление заданиями';
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="text-center">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
            </div>
        </div>
    </div>
    <div id="block_buttons">
        <div class="form-group">
            <?= Html::button('Главная', ['class' => 'btn btn-primary', 'name' => 'page-home', 'v-on:click' => 'set_page("home")']) ?>
            <?= Html::button('Сотрудники', ['class' => 'btn btn-primary', 'name' => 'page-staff', 'v-on:click' => 'set_page("staff")']) ?>
            <?= Html::button('Задания', ['class' => 'btn btn-primary', 'name' => 'page-tasks', 'v-on:click' => 'set_page("tasks")']) ?>
        </div>
    </div>
    <div id="page_home" v-if="show_page">
        <div style="margin-top: 100px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <h1>Система управления заданиями</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="page_staff" v-if="show_page">
        <div class="col-md-12">
            <div class="form-group">
                <h1>Сотрудники</h1>
                <div class="text-right">
                    <?= Html::button( 'Добавить сотрудника', ['class' => 'btn btn-success', 'name' => 'new-staff', 'v-on:click' => 'new_staff()']) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin(['id' => 'filter-staff-form']); ?>
                <div class="col-md-6">
                    <?= $form->field($model_staff, 'filter_fio')->textInput(['v-model' => 'filter_fio']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model_staff, 'filter_tasks')->textInput(['v-model' => 'filter_tasks']) ?>
                </div>
                <div class="text-right">
                    <div class="form-group">
                        <?= Html::button( 'Сбросить', ['class' => 'btn btn-primary', 'name' => 'fiter_cancel-staff', 'v-on:click' => 'filter_cancel_staff()']) ?>
                        <?= Html::submitButton('Фильтровать', ['class' => 'btn btn-success', 'name' => 'fiter-find-staff', '@click.prevent' => 'filter_find_staff()']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Фото</th>
                            <th class="text-center">ФИО</th>
                            <th class="text-center">Задания</th>
                            <th class="text-center">Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in staff_data">
                            <td class="text-center">
                                <?= Html::img('', ['v-bind:src' => 'item.photo', 'alt' => 'photo staff', 'width' => '64', 'height' => '64']) ?>
                            </td>
                            <td>{{ item.fio }}</td>
                            <td>{{ item.tasks_name }}</td>
                            <td class="text-center">
                                <?= Html::button( '<span class="glyphicon glyphicon-pencil"></span>', ['class' => 'btn btn-primary', 'name' => 'edit-staff', 'v-on:click' => 'edit_staff(item.id)']) ?>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>
    <div id="page_staff_edit" v-if="show_page">
        <div class="col-md-12">
            <div class="form-group">
                <h1>{{ page_title }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin(['id' => 'edit-staff-form']); ?>
                <div class="form-group">
                    <?= Html::button( 'Отмена', ['class' => 'btn btn-primary', 'name' => 'cancel-staff', 'v-on:click' => 'cancel_staff()']) ?>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'save-staff', '@click.prevent' => 'save_staff()']) ?>
                </div>
                <div v-if="errors.length" class="text-danger">
                    <b>Пожалуйста исправьте указанные ошибки:</b>
                    <ul>
                        <li v-for="error in errors">{{ error }}</li>
                    </ul>
                </div>
                <?= Html::hiddenInput('id', '', ['v-model' => 'id']) ?>
                <?= $form->field($model_staff, 'fio')->textInput(['autofocus' => true, 'v-model' => 'fio']) ?>
                <?= Html::hiddenInput('photo', '', ['v-model' => 'photo']) ?>
                <?php /* <?= $form->field($model_staff, 'photo')->textInput(['v-model' => 'photo']) ?> */ ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <b>Список заданий</b>
                </div>
                <table class="table">
                    <tbody>
                        <tr v-for="item in tasks_attached">
                            <td>
                                <input type="checkbox" v-bind:id="item.id" v-bind:value="item.id" v-model="checked_tasks">
                            </td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.description }}</td>
                        </tr>
                    </tbody>
                </table>
                <?php /* <span>Отмеченные задания: {{ checked_tasks }}</span> */ ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::button( 'Отмена', ['class' => 'btn btn-primary', 'name' => 'cancel-staff', 'v-on:click' => 'cancel_staff()']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'save-staff', '@click.prevent' => 'save_staff()']) ?>
        </div>
    </div>
    <div id="page_tasks" v-if="show_page">
        <div class="col-md-12">
            <div class="form-group">
                <h1>Задания</h1>
                <div class="text-right">
                    <?= Html::button( 'Добавить задание', ['class' => 'btn btn-success', 'name' => 'new-task', 'v-on:click' => 'new_task()']) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin(['id' => 'filter-tasks-form']); ?>
                <div class="col-md-4">
                    <?= $form->field($model_tasks, 'filter_name')->textInput(['v-model' => 'filter_name']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model_tasks, 'filter_description')->textInput(['v-model' => 'filter_description']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model_tasks, 'filter_status_id')->dropDownList($items_tasks, ['prompt' => 'Выберите статус', 'v-model' => 'filter_status_id']) ?>
                </div>
                <div class="text-right">
                    <div class="form-group">
                        <?= Html::button( 'Сбросить', ['class' => 'btn btn-primary', 'name' => 'fiter_cancel-tasks', 'v-on:click' => 'filter_cancel_tasks()']) ?>
                        <?= Html::submitButton('Фильтровать', ['class' => 'btn btn-success', 'name' => 'fiter-find-tasks', '@click.prevent' => 'filter_find_tasks()']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center">Наименование</th>
                        <th class="text-center">Описание</th>
                        <th class="text-center">Статус</th>
                        <th class="text-center">Сотрудники</th>
                        <th class="text-center">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in tasks_data">
                        <td>{{ item.name }}</td>
                        <td>{{ item.description }}</td>
                        <td>{{ item.status_name }}</td>
                        <td>{{ item.staff_name }}</td>
                        <td class="text-center">
                            <?= Html::button( '<span class="glyphicon glyphicon-pencil"></span>', ['class' => 'btn btn-primary', 'name' => 'edit-task', 'v-on:click' => 'edit_task(item.id)']) ?>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>
    <div id="page_tasks_edit" v-if="show_page">
        <div class="col-md-12">
            <div class="form-group">
                <h1>{{ page_title }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin(['id' => 'edit-tasks-form']); ?>
                <div class="form-group">
                    <?= Html::button( 'Отмена', ['class' => 'btn btn-primary', 'name' => 'cancel-tasks', 'v-on:click' => 'cancel_tasks()']) ?>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'save-tasks', '@click.prevent' => 'save_tasks()']) ?>
                </div>
                <div v-if="errors.length" class="text-danger">
                    <b>Пожалуйста исправьте указанные ошибки:</b>
                    <ul>
                        <li v-for="error in errors">{{ error }}</li>
                    </ul>
                </div>
                <?= Html::hiddenInput('id', '', ['v-model' => 'id']) ?>
                <?= $form->field($model_tasks, 'name')->textInput(['autofocus' => true, 'v-model' => 'name']) ?>
                <?= $form->field($model_tasks, 'description')->textarea(['rows' => 6, 'v-model' => 'description']) ?>
                <?= $form->field($model_tasks, 'ref_tasks_status_id')->dropDownList($items_tasks, ['prompt' => 'Выберите статус', 'v-model' => 'ref_tasks_status_id']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <b>Список сотрудников</b>
                </div>
                <table class="table">
                    <tbody>
                    <tr v-for="item in staff_attached">
                        <td>
                            <input type="checkbox" v-bind:id="item.id" v-bind:value="item.id" v-model="checked_staff">
                        </td>
                        <td>{{ item.fio }}</td>
                        <td class="text-center">
                            <?= Html::img('', ['v-bind:src' => 'item.photo', 'alt' => 'photo staff', 'width' => '32', 'height' => '32']) ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php /* <span>Отмеченные задания: {{ checked_staff }}</span> */ ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::button( 'Отмена', ['class' => 'btn btn-primary', 'name' => 'cancel-tasks', 'v-on:click' => 'cancel_tasks()']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name' => 'save-tasks', '@click.prevent' => 'save_tasks()']) ?>
        </div>
    </div>
</div>

<?php

$script = <<< JS

    var page_home = new Vue({
        el: '#page_home',
        data: {
            show_page: true
        },
        methods: {
            set_show_page: function(is_show) {
                this.show_page = is_show;
            }
        }
    });

    var page_staff_edit = new Vue({
        el: '#page_staff_edit',
        data: {
            show_page: false,
            staff_data_edit: null,
            page_title: '',
            id: '',
            fio: '',
            photo: '',
            tasks_attached: null,
            checked_tasks: [],
            errors: [],
            block_buttons: null
        },
        methods: {
            set_show_page: function(is_show) {
                this.show_page = is_show;
            },
            set_id: function(id) {
                this.id = id;
                this.page_title = 'Редактировать сотрудника';
            },
            clear_data: function() {
                this.id = '';
                this.fio = '';
                this.photo = '';
                this.page_title = 'Добавить нового сотрудника';
            },
            get_staff_data_edit: function() {
                axios.post('$url_get_staff_data_edit', {
                    StaffForm: {
                        id: this.id
                    }
                })
                .then(function(response) {
                    if(!response.data.error) {
                        page_staff_edit.id = response.data.data.id;
                        page_staff_edit.fio = response.data.data.fio;
                        page_staff_edit.photo = response.data.data.photo;
                        page_staff_edit.tasks_attached = response.data.data.tasks_attached;
                        page_staff_edit.checked_tasks = response.data.data.checked_tasks;
                    }
                })
                .catch(function(err) {
                  console.log(err);
                });
            },
            cancel_staff: function() {
                this.block_buttons.set_page('staff');
            },
            save_staff: function() {

                if(!this.valid_form()) return;

                axios.post('$url_set_staff_data_edit', {
                    StaffForm: {
                        id: this.id,
                        fio: this.fio,
                        photo: this.photo,
                        checked_tasks: this.checked_tasks.join(', ')
                    }
                })
                .then(function(response) {
                    if(!response.data.error) {
                        this.block_buttons.set_page('staff');
                    }
                })
                .catch(function(err) {
                  console.log(err);
                });
                
            },
            valid_form: function() {
                this.errors = [];
                is_valid = true;
                
                if(this.fio === '') {
                    this.errors.push('Требуется указать ФИО!');
                    is_valid = false;
                }
                
                return is_valid;
            }
        }
    });
    
    var page_staff = new Vue({
        el: '#page_staff',
        data: {
            show_page: false,
            staff_data: null,
            filter_fio: '',
            filter_tasks: '',
            block_buttons: null
        },
        methods: {
            set_show_page: function(is_show) {
                this.show_page = is_show;
            },
            get_staff_data: function() {
                axios.post('$url_get_staff_data', {
                    StaffForm: {
                        filter_fio: this.filter_fio,
                        filter_tasks: this.filter_tasks
                    }
                })
                .then(function(response) {
                    page_staff.staff_data = response.data.data;
                })
                .catch(function(err) {
                    console.log(err);
                });
            },
            edit_staff: function(id) {
                page_staff_edit.clear_data();
                page_staff_edit.set_id(id);
                this.block_buttons.set_page('staff_edit');
            },
            new_staff: function() {
                page_staff_edit.clear_data();
                this.block_buttons.set_page('staff_edit');
            },
            filter_cancel_staff: function() {
                this.filter_fio = '';
                this.filter_tasks = '';
                this.get_staff_data();
            },
            filter_find_staff: function() {
                this.get_staff_data();
            }
        }
    });
    
    var page_tasks_edit = new Vue({
        el: '#page_tasks_edit',
        data: {
            show_page: false,
            tasks_data_edit: null,
            page_title: '',
            id: '',
            name: '',
            description: '',
            ref_tasks_status_id: '1',
            staff_attached: null,
            errors: [],
            checked_staff: [],
            block_buttons: null
        },
        methods: {
            set_show_page: function(is_show) {
                this.show_page = is_show;
            },
            set_id: function(id) {
                this.id = id;
                this.page_title = 'Редактировать задание';
            },
            clear_data: function() {
                this.id = '';
                this.name = '';
                this.description = '';
                this.ref_tasks_status_id = '';
                this.page_title = 'Добавить новое задание';
            },
            get_tasks_data_edit: function() {

                axios.post('$url_get_tasks_data_edit', {
                    TasksForm: {
                        id: this.id
                    }
                })
                .then(function(response) {
                    if(!response.data.error) {
                        page_tasks_edit.id = response.data.data.id;
                        page_tasks_edit.name = response.data.data.name;
                        page_tasks_edit.description = response.data.data.description;
                        page_tasks_edit.ref_tasks_status_id = response.data.data.ref_tasks_status_id;
                        page_tasks_edit.staff_attached = response.data.data.staff_attached;
                        page_tasks_edit.checked_staff = response.data.data.checked_staff;
                    }
                })
                .catch(function(err) {
                    console.log(err);
                });

            },
            cancel_tasks: function() {
                this.block_buttons.set_page('tasks');
            },
            save_tasks: function() {
                
                if(!this.valid_form()) return;
                
                axios.post('$url_set_tasks_data_edit', {
                    TasksForm: {
                        id: this.id,
                        name: this.name,
                        description: this.description,
                        ref_tasks_status_id: this.ref_tasks_status_id,
                        checked_staff: this.checked_staff.join(', ')
                    }
                })
                .then(function(response) {
                    if(!response.data.error) {
                        this.block_buttons.set_page('tasks');
                    }
                })
                .catch(function(err) {
                    console.log(err);
                });
                
            },
            valid_form: function() {
                this.errors = [];
                is_valid = true;
                
                if(this.name === '') {
                    this.errors.push('Требуется указать Наименование!');
                    is_valid = false;
                }
                
                if(this.description === '') {
                    this.errors.push('Требуется указать Описание!');
                    is_valid = false;
                }

                if(this.ref_tasks_status_id === '') {
                    this.errors.push('Требуется указать Статус!');
                    is_valid = false;
                }
                
                return is_valid;
            }
        }
    });
    
    var page_tasks = new Vue({
        el: '#page_tasks',
        data: {
            show_page: false,
            tasks_data: null,
            filter_name: '',
            filter_description: '',
            filter_status_id: '',
            block_buttons: null
        },
        methods: {
            set_show_page: function(is_show) {
                this.show_page = is_show;
            },
            get_tasks_data: function() {
                axios.post('$url_get_tasks_data', {
                    TasksForm: {
                        filter_name: this.filter_name,
                        filter_description: this.filter_description,
                        filter_status_id: this.filter_status_id
                    }
                })
                .then(function(response) {
                    page_tasks.tasks_data = response.data.data;
                })
                .catch(function(err) {
                    console.log(err);
                });
            },
            edit_task: function(id) {
                page_tasks_edit.clear_data();
                page_tasks_edit.set_id(id);
                this.block_buttons.set_page('tasks_edit');
            },
            new_task: function() {
                page_tasks_edit.clear_data();
                this.block_buttons.set_page('tasks_edit');
            },
            filter_cancel_tasks: function() {
                this.filter_name = '';
                this.filter_description = '';
                this.filter_status_id = '';
                this.get_tasks_data();
            },
            filter_find_tasks: function() {
                this.get_tasks_data();
            }
        }
    });
    
    var block_buttons = new Vue({
        el: '#block_buttons',
        data: {
            current_page: 'home'
        },
        methods: {
            set_page: function(page) {

                if(page === this.current_page) return;

                page_home.set_show_page(false);
                page_staff.set_show_page(false);
                page_staff_edit.set_show_page(false);
                page_tasks.set_show_page(false);
                page_tasks_edit.set_show_page(false);

                switch (page) {
                    
                    case 'home':
                        page_home.set_show_page(true);
                        this.current_page = 'home';
                        break;
                    
                    case 'staff_edit':
                        page_staff_edit.set_show_page(true);
                        page_staff_edit.get_staff_data_edit();
                        this.current_page = 'staff_edit';
                        break;

                    case 'staff':
                        page_staff.set_show_page(true);
                        page_staff.get_staff_data();
                        this.current_page = 'staff';
                        break;

                    case 'tasks_edit':
                        page_tasks_edit.set_show_page(true);
                        page_tasks_edit.get_tasks_data_edit();
                        this.current_page = 'tasks_edit';
                        break;

                    case 'tasks':
                        page_tasks.set_show_page(true);
                        page_tasks.get_tasks_data();
                        this.current_page = 'tasks';
                        break;
                }
            }
        },
        mounted: function() {
            page_staff.block_buttons = this;
            page_staff_edit.block_buttons = this;
            page_tasks.block_buttons = this;
            page_tasks_edit.block_buttons = this;
        }
    });

JS;

$this->registerJs(
    $script,
    yii\web\View::POS_END
);

?>