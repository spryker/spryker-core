import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { InputModule } from '@spryker/input';
import { CheckboxModule } from '@spryker/checkbox';
import { AutogenerateInputComponent } from './autogenerate-input.component';

@NgModule({
    imports: [CommonModule, InputModule, CheckboxModule],
    declarations: [AutogenerateInputComponent],
    exports: [AutogenerateInputComponent],
})
export class AutogenerateInputModule {}
