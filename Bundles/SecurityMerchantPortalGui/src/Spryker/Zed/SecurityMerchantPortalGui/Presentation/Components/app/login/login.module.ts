import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardModule } from '@spryker/card';
import { LogoModule } from '@spryker/logo';

import { LoginComponent } from './login.component';

@NgModule({
    imports: [CommonModule, LogoModule, CardModule],
    declarations: [LoginComponent],
    exports: [LoginComponent],
})
export class LoginModule {}
