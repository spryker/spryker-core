import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LocaleModule } from '@spryker/locale';
import { AuthFooterComponent } from './auth-footer.component';

@NgModule({
    imports: [CommonModule, LocaleModule],
    exports: [AuthFooterComponent],
    declarations: [AuthFooterComponent],
})
export class AuthFooterModule {
}
