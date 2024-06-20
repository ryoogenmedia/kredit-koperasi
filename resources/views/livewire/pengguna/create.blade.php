<div>
    <x-slot name="title">Tambah Pengguna</x-slot>

    <x-slot name="pagePretitle">Menambah Data Pengguna</x-slot>

    <x-slot name="pageTitle">Tambah Pengguna</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('pengguna.index')" />
    </x-slot>

    <x-alert />

    <form class="card" wire:submit.prevent="save" autocomplete="off">
        <div class="card-header">
            Tambah data pengguna
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="username" name="username" label="Username" placeholder="masukkan username"
                        type="text" />
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="email" name="email" label="Masukkan Email" placeholder="masukkan email"
                        type="text" />
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="password" name="password" label="Password" placeholder="**********"
                        type="password" />
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="avatar" name="avatar" label="Avatar" placeholder="masukkan avatar"
                        type="file" />
                </div>
                <div class="col-12 col-lg-6">
                    <x-form.select wire:model.lazy="roles" name="roles" label="Peran Akun">
                        <option selected value=""> - Pilih - </option>
                        @foreach (config('const.roles') as $roles)
                            <option wire:key="row-{{ $roles }}" value="{{ $roles }}">
                                {{ ucwords($roles) == 'User' ? 'Nasabah' : ucwords($roles) }}
                            </option>
                        @endforeach
                    </x-form.select>
                </div>
            </div>
        </div>
        @if ($this->roles == 'user')
        <div class="card-body">
            <div class="row">

                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="nomorKTP" name="nomorKTP" label="Nomor KTP" placeholder="masukkan nomor KTP"
                        type="text" />
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="namaNasabah" name="namaNasabah" label="Nama Nasabah" placeholder="masukkan nama Nasabah"
                        type="text" />
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="umurNasabah" name="umurNasabah" label="Umur Nasabah" placeholder="masukkan umur"
                        type="text" />
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.input wire:model="nomorPonsel" name="nomorPonsel" label="Nomor Ponsel" placeholder="masukkan nomor ponsel"
                        type="text" />
                </div>

                <div class="col-12 col-lg-6">
                    <x-form.textarea wire:model="alamat" name="alamat" label="Alamat Lengkap"
                    placeholder="masukkan alamat" type="text" />
                </div>
            </div>
        </div>
    @endif
        <div class="card-footer">
            <div class="btn-list justify-content-end">
                <button type="reset" class="btn">Reset</button>

                <x-datatable.button.save target="save" />
            </div>
        </div>
    </form>
</div>
