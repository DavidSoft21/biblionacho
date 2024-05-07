import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LendbookStoreComponent } from './lendbook-store.component';

describe('LendbookStoreComponent', () => {
  let component: LendbookStoreComponent;
  let fixture: ComponentFixture<LendbookStoreComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [LendbookStoreComponent]
    });
    fixture = TestBed.createComponent(LendbookStoreComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
