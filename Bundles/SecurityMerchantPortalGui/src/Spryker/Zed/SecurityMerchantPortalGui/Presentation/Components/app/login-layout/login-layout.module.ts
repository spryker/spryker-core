import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardModule } from '@spryker/card';
import { LogoModule } from '@spryker/logo';

import { LoginLayoutComponent } from './login-layout.component';

@NgModule({
    imports: [CommonModule, LogoModule, CardModule],
    declarations: [LoginLayoutComponent],
    exports: [LoginLayoutComponent],
})
export class LoginLayoutModule {}
