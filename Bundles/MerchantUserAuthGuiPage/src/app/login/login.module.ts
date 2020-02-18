import { NgModule } from '@angular/core';
import { CardModule } from '@spryker/card';
import { LogoModule } from '@spryker/logo';

import { LoginComponent } from './login.component';

@NgModule({
    imports: [LogoModule, CardModule],
    declarations: [LoginComponent],
    exports: [LoginComponent],
})
export class LoginModule {}
