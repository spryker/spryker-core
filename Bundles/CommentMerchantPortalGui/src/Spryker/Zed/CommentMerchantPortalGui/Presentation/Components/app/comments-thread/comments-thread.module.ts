import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { CustomElementBoundaryModule } from '@spryker/web-components';
import { AddCommentModule } from '../add-comment/add-comment.module';
import { CommentModule } from '../comment/comment.module';
import { CommentsThreadComponent } from './comments-thread.component';

@NgModule({
    imports: [CustomElementBoundaryModule, CommonModule, AddCommentModule, CommentModule],
    declarations: [CommentsThreadComponent],
    exports: [CommentsThreadComponent],
})
export class CommentsThreadModule {}
