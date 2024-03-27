import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { ButtonModule } from '@spryker/button';
import { IconModule } from '@spryker/icon';
import { SpinnerModule } from '@spryker/spinner';
import { TextareaModule } from '@spryker/textarea';
import { IconEditModule, IconTrashModule } from '../../icons';
import { CommentComponent } from './comment.component';
import { LocalTimePipe } from './local-time.pipe';

@NgModule({
    imports: [SpinnerModule, CommonModule, IconEditModule, IconTrashModule, IconModule, ButtonModule, TextareaModule],
    declarations: [CommentComponent, LocalTimePipe],
    exports: [CommentComponent],
})
export class CommentModule {}
